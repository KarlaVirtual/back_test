<?php namespace Backend\mysql;
/** 
* Clase 'WsEncMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'WsEnc'
* 
* Ejemplo de uso: 
* $WsEncMySqlDAO = new WsEncMySqlDAO();
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class WsEncMySqlDAO implements WsEncDAO
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
		$sql = 'SELECT * FROM ws_enc WHERE id = ?';
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
		$sql = 'SELECT * FROM ws_enc';
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
		$sql = 'SELECT * FROM ws_enc ORDER BY '.$orderColumn;
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
		$sql = 'DELETE FROM ws_enc WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($id);
		return $this->executeUpdate($sqlQuery);
	}
	
    /**
     * Insertar un registro en la base de datos
     *
     * @param Objeto wsEnc wsEnc
     *
     * @return String $id resultado de la consulta
     *
     */
	public function insert($wsEnc){
		$sql = 'INSERT INTO ws_enc (transaccion_id, ticket_id, vlr_apuesta, vlr_premio, usuario_id, premiado, premio_pagado, fecha_pago, fecha_cierre, estado, fecha_crea, usucrea_id, fecha_modif, usumodif_id, mandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($wsEnc->transaccionId);
		$sqlQuery->set($wsEnc->ticketId);
		$sqlQuery->set($wsEnc->vlrApuesta);
		$sqlQuery->set($wsEnc->vlrPremio);
		$sqlQuery->set($wsEnc->usuarioId);
		$sqlQuery->set($wsEnc->premiado);
		$sqlQuery->set($wsEnc->premioPagado);
		$sqlQuery->set($wsEnc->fechaPago);
		$sqlQuery->set($wsEnc->fechaCierre);
		$sqlQuery->set($wsEnc->estado);
		$sqlQuery->set($wsEnc->fechaCrea);
		$sqlQuery->set($wsEnc->usucreaId);
		$sqlQuery->set($wsEnc->fechaModif);
		$sqlQuery->set($wsEnc->usumodifId);
		$sqlQuery->set($wsEnc->mandante);

		$id = $this->executeInsert($sqlQuery);	
		$wsEnc->id = $id;
		return $id;
	}

    /**
     * Editar un registro en la base de datos
     *
     * @param Objeto wsEnc wsEnc
     *
     * @return boolean $ resultado de la consulta
     *
     */
	public function update($wsEnc){
		$sql = 'UPDATE ws_enc SET transaccion_id = ?, ticket_id = ?, vlr_apuesta = ?, vlr_premio = ?, usuario_id = ?, premiado = ?, premio_pagado = ?, fecha_pago = ?, fecha_cierre = ?, estado = ?, fecha_crea = ?, usucrea_id = ?, fecha_modif = ?, usumodif_id = ?, mandante = ? WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($wsEnc->transaccionId);
		$sqlQuery->set($wsEnc->ticketId);
		$sqlQuery->set($wsEnc->vlrApuesta);
		$sqlQuery->set($wsEnc->vlrPremio);
		$sqlQuery->set($wsEnc->usuarioId);
		$sqlQuery->set($wsEnc->premiado);
		$sqlQuery->set($wsEnc->premioPagado);
		$sqlQuery->set($wsEnc->fechaPago);
		$sqlQuery->set($wsEnc->fechaCierre);
		$sqlQuery->set($wsEnc->estado);
		$sqlQuery->set($wsEnc->fechaCrea);
		$sqlQuery->set($wsEnc->usucreaId);
		$sqlQuery->set($wsEnc->fechaModif);
		$sqlQuery->set($wsEnc->usumodifId);
		$sqlQuery->set($wsEnc->mandante);

		$sqlQuery->set($wsEnc->id);
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
		$sql = 'DELETE FROM ws_enc';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}


    /**
     * Obtener todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByTransaccionId($value){
		$sql = 'SELECT * FROM ws_enc WHERE transaccion_id = ?';
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
	public function queryByTicketId($value){
		$sql = 'SELECT * FROM ws_enc WHERE ticket_id = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE vlr_apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna vlr_premio sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_premio requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByVlrPremio($value){
		$sql = 'SELECT * FROM ws_enc WHERE vlr_premio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
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
		$sql = 'SELECT * FROM ws_enc WHERE usuario_id = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna premio_pagado sea igual al valor pasado como parámetro
     *
     * @param String $value premio_pagado requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByPremioPagado($value){
		$sql = 'SELECT * FROM ws_enc WHERE premio_pagado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return Array $ resultado de la consulta
     *
     */
	public function queryByFechaPago($value){
		$sql = 'SELECT * FROM ws_enc WHERE fecha_pago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

    /**
     * Obtener todos los registros donde se encuentre que
     * la columna fecha_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cierre requerido
     *
     * @return Array $ resultado de la consulta
     *
     */	
	public function queryByFechaCierre($value){
		$sql = 'SELECT * FROM ws_enc WHERE fecha_cierre = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE estado = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE fecha_modif = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE usumodif_id = ?';
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
		$sql = 'SELECT * FROM ws_enc WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}


    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna transaccion_id sea igual al valor pasado como parámetro
     *
     * @param String $value transaccion_id requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByTransaccionId($value){
		$sql = 'DELETE FROM ws_enc WHERE transaccion_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'DELETE FROM ws_enc WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_apuesta sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_apuesta requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByVlrApuesta($value){
		$sql = 'DELETE FROM ws_enc WHERE vlr_apuesta = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna vlr_premio sea igual al valor pasado como parámetro
     *
     * @param String $value vlr_premio requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByVlrPremio($value){
		$sql = 'DELETE FROM ws_enc WHERE vlr_premio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
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
		$sql = 'DELETE FROM ws_enc WHERE usuario_id = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE premiado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna premio_pagado sea igual al valor pasado como parámetro
     *
     * @param String $value premio_pagado requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByPremioPagado($value){
		$sql = 'DELETE FROM ws_enc WHERE premio_pagado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_pago sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_pago requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaPago($value){
		$sql = 'DELETE FROM ws_enc WHERE fecha_pago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

    /**
     * Eliminar todos los registros donde se encuentre que
     * la columna fecha_cierre sea igual al valor pasado como parámetro
     *
     * @param String $value fecha_cierre requerido
     *
     * @return boolean $ resultado de la ejecución
     *
     */
	public function deleteByFechaCierre($value){
		$sql = 'DELETE FROM ws_enc WHERE fecha_cierre = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE estado = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE fecha_modif = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE usumodif_id = ?';
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
		$sql = 'DELETE FROM ws_enc WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}




    /**
     * Crear y devolver un objeto del tipo WsEnc
     * con los valores de una consulta sql
     * 
     *
     * @param Arreglo $row arreglo asociativo
     *
     * @return Objeto $wsEnc WsEnc
     *
     * @access protected
     *
     */
	protected function readRow($row){
		$wsEnc = new WsEnc();
		
		$wsEnc->id = $row['id'];
		$wsEnc->transaccionId = $row['transaccion_id'];
		$wsEnc->ticketId = $row['ticket_id'];
		$wsEnc->vlrApuesta = $row['vlr_apuesta'];
		$wsEnc->vlrPremio = $row['vlr_premio'];
		$wsEnc->usuarioId = $row['usuario_id'];
		$wsEnc->premiado = $row['premiado'];
		$wsEnc->premioPagado = $row['premio_pagado'];
		$wsEnc->fechaPago = $row['fecha_pago'];
		$wsEnc->fechaCierre = $row['fecha_cierre'];
		$wsEnc->estado = $row['estado'];
		$wsEnc->fechaCrea = $row['fecha_crea'];
		$wsEnc->usucreaId = $row['usucrea_id'];
		$wsEnc->fechaModif = $row['fecha_modif'];
		$wsEnc->usumodifId = $row['usumodif_id'];
		$wsEnc->mandante = $row['mandante'];

		return $wsEnc;
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
     */	protected function execute($sqlQuery){
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