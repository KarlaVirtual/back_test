<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'WsEnc'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface WsEncDAO{

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
 	 * @param String $id llave primaria
 	 */
	public function delete($id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String wsEnc wsEnc
 	 */
	public function insert($wsEnc);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object wsEnc wsEnc
 	 */
	public function update($wsEnc);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function queryByTransaccionId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function queryByTicketId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */	
	public function queryByVlrApuesta($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrPremio requerido
 	 */	
	public function queryByVlrPremio($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function queryByPremiado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PremioPagado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioPagado requerido
 	 */	
	public function queryByPremioPagado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function queryByFechaPago($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCierre requerido
 	 */	
	public function queryByFechaCierre($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function queryByUsumodifId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function deleteByTransaccionId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrApuesta sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrApuesta requerido
 	 */	
	public function deleteByVlrApuesta($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrPremio requerido
 	 */	
	public function deleteByVlrPremio($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function deleteByPremiado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PremioPagado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PremioPagado requerido
 	 */	
	public function deleteByPremioPagado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function deleteByFechaPago($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCierre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCierre requerido
 	 */	
	public function deleteByFechaCierre($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function deleteByUsumodifId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>