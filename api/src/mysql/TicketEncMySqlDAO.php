<?php namespace Backend\mysql;
/** 
* Clase 'TicketEncMySqlDAO'
* 
* Esta clase provee las consultas del modelo o tabla 'TicketEnc'
* 
* Ejemplo de uso: 
* $TicketEncMySqlDAO = new TicketEncMySqlDAO();
*	
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
class TicketEncMySqlDAO implements TicketEncDAO{

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
		$sql = 'SELECT * FROM ticket_enc WHERE ticket_id = ?';
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
		$sql = 'SELECT * FROM ticket_enc';
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
		$sql = 'SELECT * FROM ticket_enc ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $ticket_id llave primaria
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function delete($ticket_id){
		$sql = 'DELETE FROM ticket_enc WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($ticket_id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Objeto ticketEnc ticketEnc
 	 *
	 * @return String $id resultado de la consulta
     *
 	 */
	public function insert($ticketEnc){
		$sql = 'INSERT INTO ticket_enc (fecha_crea, hora_crea, usucrea_id, vlr_apuesta, estado, clave, formapago1_id, formapago2_id, valor_forma1, valor_forma2, bono_id, vlr_premio, premiado, premio_pagado, usumodifica_id, fecha_modifica, fecha_maxpago, fecha_premio, devolucion, es_devolucion, dir_ip, impreso, mandante, promocional_id, valor_promocional, fecha_cierre, hora_cierre) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($ticketEnc->fechaCrea);
		$sqlQuery->set($ticketEnc->horaCrea);
		$sqlQuery->set($ticketEnc->usucreaId);
		$sqlQuery->set($ticketEnc->vlrApuesta);
		$sqlQuery->set($ticketEnc->estado);
		$sqlQuery->set($ticketEnc->clave);
		$sqlQuery->set($ticketEnc->formapago1Id);
		$sqlQuery->set($ticketEnc->formapago2Id);
		$sqlQuery->set($ticketEnc->valorForma1);
		$sqlQuery->set($ticketEnc->valorForma2);
		$sqlQuery->set($ticketEnc->bonoId);
		$sqlQuery->set($ticketEnc->vlrPremio);
		$sqlQuery->set($ticketEnc->premiado);
		$sqlQuery->set($ticketEnc->premioPagado);
		$sqlQuery->set($ticketEnc->usumodificaId);
		$sqlQuery->set($ticketEnc->fechaModifica);
		$sqlQuery->set($ticketEnc->fechaMaxpago);
		$sqlQuery->set($ticketEnc->fechaPremio);
		$sqlQuery->set($ticketEnc->devolucion);
		$sqlQuery->set($ticketEnc->esDevolucion);
		$sqlQuery->set($ticketEnc->dirIp);
		$sqlQuery->set($ticketEnc->impreso);
		$sqlQuery->set($ticketEnc->mandante);
		$sqlQuery->set($ticketEnc->promocionalId);
		$sqlQuery->set($ticketEnc->valorPromocional);
		$sqlQuery->set($ticketEnc->fechaCierre);
		$sqlQuery->set($ticketEnc->horaCierre);

		$id = $this->executeInsert($sqlQuery);	
		$ticketEnc->ticketId = $id;
		return $id;
	}
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Objeto ticketEnc ticketEnc
 	 *
	 * @return boolean $ resultado de la consulta
     *
 	 */
	public function update($ticketEnc){
		$sql = 'UPDATE ticket_enc SET fecha_crea = ?, hora_crea = ?, usucrea_id = ?, vlr_apuesta = ?, estado = ?, clave = ?, formapago1_id = ?, formapago2_id = ?, valor_forma1 = ?, valor_forma2 = ?, bono_id = ?, vlr_premio = ?, premiado = ?, premio_pagado = ?, usumodifica_id = ?, fecha_modifica = ?, fecha_maxpago = ?, fecha_premio = ?, devolucion = ?, es_devolucion = ?, dir_ip = ?, impreso = ?, mandante = ?, promocional_id = ?, valor_promocional = ?, fecha_cierre = ?, hora_cierre = ? WHERE ticket_id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($ticketEnc->fechaCrea);
		$sqlQuery->set($ticketEnc->horaCrea);
		$sqlQuery->set($ticketEnc->usucreaId);
		$sqlQuery->set($ticketEnc->vlrApuesta);
		$sqlQuery->set($ticketEnc->estado);
		$sqlQuery->set($ticketEnc->clave);
		$sqlQuery->set($ticketEnc->formapago1Id);
		$sqlQuery->set($ticketEnc->formapago2Id);
		$sqlQuery->set($ticketEnc->valorForma1);
		$sqlQuery->set($ticketEnc->valorForma2);
		$sqlQuery->set($ticketEnc->bonoId);
		$sqlQuery->set($ticketEnc->vlrPremio);
		$sqlQuery->set($ticketEnc->premiado);
		$sqlQuery->set($ticketEnc->premioPagado);
		$sqlQuery->set($ticketEnc->usumodificaId);
		$sqlQuery->set($ticketEnc->fechaModifica);
		$sqlQuery->set($ticketEnc->fechaMaxpago);
		$sqlQuery->set($ticketEnc->fechaPremio);
		$sqlQuery->set($ticketEnc->devolucion);
		$sqlQuery->set($ticketEnc->esDevolucion);
		$sqlQuery->set($ticketEnc->dirIp);
		$sqlQuery->set($ticketEnc->impreso);
		$sqlQuery->set($ticketEnc->mandante);
		$sqlQuery->set($ticketEnc->promocionalId);
		$sqlQuery->set($ticketEnc->valorPromocional);
		$sqlQuery->set($ticketEnc->fechaCierre);
		$sqlQuery->set($ticketEnc->horaCierre);

		$sqlQuery->set($ticketEnc->ticketId);
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
		$sql = 'DELETE FROM ticket_enc';
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
		$sql = 'SELECT * FROM ticket_enc WHERE fecha_crea = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE hora_crea = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE usucrea_id = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE vlr_apuesta = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value clave requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByClave($value){
		$sql = 'SELECT * FROM ticket_enc WHERE clave = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna formapago1_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value formapago1_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFormapago1Id($value){
		$sql = 'SELECT * FROM ticket_enc WHERE formapago1_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna formapago2_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value formapago2_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFormapago2Id($value){
		$sql = 'SELECT * FROM ticket_enc WHERE formapago2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna valor_forma1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor_forma1 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorForma1($value){
		$sql = 'SELECT * FROM ticket_enc WHERE valor_forma1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna valor_forma2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor_forma2 requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorForma2($value){
		$sql = 'SELECT * FROM ticket_enc WHERE valor_forma2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna bono_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value bono_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByBonoId($value){
		$sql = 'SELECT * FROM ticket_enc WHERE bono_id = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE vlr_premio = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE premiado = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE premio_pagado = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE usumodifica_id = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE fecha_modifica = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_maxpago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_maxpago requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaMaxpago($value){
		$sql = 'SELECT * FROM ticket_enc WHERE fecha_maxpago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna fecha_premio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_premio requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByFechaPremio($value){
		$sql = 'SELECT * FROM ticket_enc WHERE fecha_premio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value devolucion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByDevolucion($value){
		$sql = 'SELECT * FROM ticket_enc WHERE devolucion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna es_devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value es_devolucion requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByEsDevolucion($value){
		$sql = 'SELECT * FROM ticket_enc WHERE es_devolucion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna dir_ip sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value dir_ip requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByDirIp($value){
		$sql = 'SELECT * FROM ticket_enc WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna impreso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value impreso requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByImpreso($value){
		$sql = 'SELECT * FROM ticket_enc WHERE impreso = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna promocional_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value promocional_id requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByPromocionalId($value){
		$sql = 'SELECT * FROM ticket_enc WHERE promocional_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna valor_promocional sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor_promocional requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByValorPromocional($value){
		$sql = 'SELECT * FROM ticket_enc WHERE valor_promocional = ?';
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
		$sql = 'SELECT * FROM ticket_enc WHERE fecha_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna hora_cierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value hora_cierre requerido
 	 *
	 * @return Array $ resultado de la consulta
	 *
 	 */
	public function queryByHoraCierre($value){
		$sql = 'SELECT * FROM ticket_enc WHERE hora_cierre = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE fecha_crea = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE hora_crea = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE usucrea_id = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE vlr_apuesta = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE estado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value clave requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByClave($value){
		$sql = 'DELETE FROM ticket_enc WHERE clave = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna formapago1_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value formapago1_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFormapago1Id($value){
		$sql = 'DELETE FROM ticket_enc WHERE formapago1_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna formapago2_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value formapago2_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFormapago2Id($value){
		$sql = 'DELETE FROM ticket_enc WHERE formapago2_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna valor_forma1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor_forma1 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValorForma1($value){
		$sql = 'DELETE FROM ticket_enc WHERE valor_forma1 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna valor_forma2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor_forma2 requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValorForma2($value){
		$sql = 'DELETE FROM ticket_enc WHERE valor_forma2 = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna bono_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value bono_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByBonoId($value){
		$sql = 'DELETE FROM ticket_enc WHERE bono_id = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE vlr_premio = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE premiado = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE premio_pagado = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna usumodifica_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value usumodifica_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByUsumodificaId($value){
		$sql = 'DELETE FROM ticket_enc WHERE usumodifica_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_modifica sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_modifica requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaModifica($value){
		$sql = 'DELETE FROM ticket_enc WHERE fecha_modifica = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_maxpago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_maxpago requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaMaxpago($value){
		$sql = 'DELETE FROM ticket_enc WHERE fecha_maxpago = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna fecha_premio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value fecha_premio requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByFechaPremio($value){
		$sql = 'DELETE FROM ticket_enc WHERE fecha_premio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value devolucion requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByDevolucion($value){
		$sql = 'DELETE FROM ticket_enc WHERE devolucion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna es_devolucion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value es_devolucion requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByEsDevolucion($value){
		$sql = 'DELETE FROM ticket_enc WHERE es_devolucion = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna dir_ip sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value dir_ip requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByDirIp($value){
		$sql = 'DELETE FROM ticket_enc WHERE dir_ip = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna impreso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value impreso requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByImpreso($value){
		$sql = 'DELETE FROM ticket_enc WHERE impreso = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE mandante = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna promocional_id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value promocional_id requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByPromocionalId($value){
		$sql = 'DELETE FROM ticket_enc WHERE promocional_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna valor_promocional sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value valor_promocional requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByValorPromocional($value){
		$sql = 'DELETE FROM ticket_enc WHERE valor_promocional = ?';
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
		$sql = 'DELETE FROM ticket_enc WHERE fecha_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna hora_cierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value hora_cierre requerido
 	 *
 	 * @return boolean $ resultado de la ejecución
 	 *
 	 */
	public function deleteByHoraCierre($value){
		$sql = 'DELETE FROM ticket_enc WHERE hora_cierre = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	



	
	/**
 	 * Crear y devolver un objeto del tipo SaldoUsuonlineAjuste
 	 * con los valores de una consulta sql
 	 * 
 	 *
 	 * @param Arreglo $row arreglo asociativo
 	 *
 	 * @return Objeto $saldoUsuonlineAjuste SaldoUsuonlineAjuste
 	 *
 	 * @access protected
 	 *
 	 */
	protected function readRow($row){
		$ticketEnc = new TicketEnc();
		
		$ticketEnc->ticketId = $row['ticket_id'];
		$ticketEnc->fechaCrea = $row['fecha_crea'];
		$ticketEnc->horaCrea = $row['hora_crea'];
		$ticketEnc->usucreaId = $row['usucrea_id'];
		$ticketEnc->vlrApuesta = $row['vlr_apuesta'];
		$ticketEnc->estado = $row['estado'];
		$ticketEnc->clave = $row['clave'];
		$ticketEnc->formapago1Id = $row['formapago1_id'];
		$ticketEnc->formapago2Id = $row['formapago2_id'];
		$ticketEnc->valorForma1 = $row['valor_forma1'];
		$ticketEnc->valorForma2 = $row['valor_forma2'];
		$ticketEnc->bonoId = $row['bono_id'];
		$ticketEnc->vlrPremio = $row['vlr_premio'];
		$ticketEnc->premiado = $row['premiado'];
		$ticketEnc->premioPagado = $row['premio_pagado'];
		$ticketEnc->usumodificaId = $row['usumodifica_id'];
		$ticketEnc->fechaModifica = $row['fecha_modifica'];
		$ticketEnc->fechaMaxpago = $row['fecha_maxpago'];
		$ticketEnc->fechaPremio = $row['fecha_premio'];
		$ticketEnc->devolucion = $row['devolucion'];
		$ticketEnc->esDevolucion = $row['es_devolucion'];
		$ticketEnc->dirIp = $row['dir_ip'];
		$ticketEnc->impreso = $row['impreso'];
		$ticketEnc->mandante = $row['mandante'];
		$ticketEnc->promocionalId = $row['promocional_id'];
		$ticketEnc->valorPromocional = $row['valor_promocional'];
		$ticketEnc->fechaCierre = $row['fecha_cierre'];
		$ticketEnc->horaCierre = $row['hora_cierre'];

		return $ticketEnc;
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