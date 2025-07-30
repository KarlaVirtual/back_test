<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'TransaccionJuego'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @author      Desconocido
 * @version     1.0
 * @since       No definida
 */
interface TransaccionJuegoDAO{

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
 	 * @param String $transjuego_id llave primaria
 	 */
	public function delete($transjuego_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String transaccionJuego transaccionJuego
 	 */
	public function insert($transaccionJuego);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object transaccionJuego transaccionJuego
 	 */
	public function update($transaccionJuego);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ProductoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoId requerido
 	 */	
	public function queryByProductoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorTicket sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorTicket requerido
 	 */	
	public function queryByValorTicket($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorPremio requerido
 	 */	
	public function queryByValorPremio($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function queryByPremiado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function queryByTicketId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function queryByTransaccionId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function queryByFechaPago($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function queryByClave($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function queryByUsumodifId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProductoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoId requerido
 	 */	
	public function deleteByProductoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorTicket sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorTicket requerido
 	 */	
	public function deleteByValorTicket($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorPremio sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorPremio requerido
 	 */	
	public function deleteByValorPremio($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Premiado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Premiado requerido
 	 */	
	public function deleteByPremiado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TicketId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TicketId requerido
 	 */	
	public function deleteByTicketId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TransaccionId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransaccionId requerido
 	 */	
	public function deleteByTransaccionId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function deleteByFechaPago($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function deleteByClave($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function deleteByUsumodifId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);


}
?>