<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'WsDet'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface WsDetDAO{

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
 	 * @param String $det_id llave primaria
 	 */
	public function delete($det_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String wsDet wsDet
 	 */
	public function insert($wsDet);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object wsDet wsDet
 	 */
	public function update($wsDet);	

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
 	 * la columna EventoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EventoId requerido
 	 */	
	public function queryByEventoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function queryByDescripcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Opcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Opcion requerido
 	 */	
	public function queryByOpcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Logro requerido
 	 */	
	public function queryByLogro($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function queryByPremiado($value);

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
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EventoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EventoId requerido
 	 */	
	public function deleteByEventoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function deleteByDescripcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Opcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Opcion requerido
 	 */	
	public function deleteByOpcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Logro sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Logro requerido
 	 */	
	public function deleteByLogro($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function deleteByPremiado($value);

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