<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ItTransaccion'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ItTransaccionDAO{

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
	public function delete($it_cuentatrans_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String itainment itainment
 	 */
	public function insert($itTransaccion);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object itainment itainment
 	 */
	public function update($itTransaccion);	

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
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna GameReference sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameReference requerido
 	 */	
	public function queryByGameReference($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna BetStatus sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BetStatus requerido
 	 */	
	public function queryByBetStatus($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna HoraCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCrea requerido
 	 */	
	public function queryByHoraCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);





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
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function deleteByValor($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna GameReference sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameReference requerido
 	 */	
	public function deleteByGameReference($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna BetStatus sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value BetStatus requerido
 	 */	
	public function deleteByBetStatus($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna HoraCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value HoraCrea requerido
 	 */	
	public function deleteByHoraCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function deleteByTipo($value);


}
?>