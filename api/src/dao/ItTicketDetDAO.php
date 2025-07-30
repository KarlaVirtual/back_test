<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ItTicketDet'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ItTicketDetDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $id llave primaria
	 */
	public function load($id);

	/**
	 * Obtener todos los registros de la base datos
	 */
	public function queryAll();
	
	/**
	 * Obtener todos los registros
	 * ordenadas por el nombre de la columna 
	 * que se pasa como parámetro
	 *
	 * @param String $orderColumn nombre de la columna
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Eliminar todos los registros condicionados
 	 * por la llave primaria
 	 *
 	 * @param String $it_ticketdet_id llave primaria
 	 */
	public function delete($it_ticketdet_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String itTicketDet itTicketDet
 	 */
	public function insert($itTicketDet);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object itTicketDet itTicketDet
 	 */
	public function update($itTicketDet);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function queryByTicketId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Apuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apuesta requerido
 	 */	
	public function queryByApuesta($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Agrupador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Agrupador requerido
 	 */	
	public function queryByAgrupador($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Logro requerido
 	 */	
	public function queryByLogro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Opcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Opcion requerido
 	 */	
	public function queryByOpcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ApuestaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ApuestaId requerido
 	 */	
	public function queryByApuestaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna AgrupadorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value AgrupadorId requerido
 	 */	
	public function queryByAgrupadorId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaEvento requerido
 	 */	
	public function queryByFechaEvento($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraEvento requerido
 	 */	
	public function queryByHoraEvento($value);







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Apuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apuesta requerido
 	 */	
	public function deleteByApuesta($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Agrupador sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Agrupador requerido
 	 */	
	public function deleteByAgrupador($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Logro requerido
 	 */	
	public function deleteByLogro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Opcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Opcion requerido
 	 */	
	public function deleteByOpcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ApuestaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ApuestaId requerido
 	 */	
	public function deleteByApuestaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna AgrupadorId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value AgrupadorId requerido
 	 */	
	public function deleteByAgrupadorId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaEvento requerido
 	 */	
	public function deleteByFechaEvento($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraEvento sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraEvento requerido
 	 */	
	public function deleteByHoraEvento($value);


}
?>