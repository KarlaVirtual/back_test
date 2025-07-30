<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'TransaccionProducto'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @author      Desconocido
 * @version     1.0
 * @since       No definida
 */
interface TransaccionProductoDAO{

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
 	 * @param String $transproducto_id llave primaria
 	 */
	public function delete($transproducto_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String transaccionProducto transaccionProducto
 	 */
	public function insert($transaccionProducto);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object transaccionProducto transaccionProducto
 	 */
	public function update($transaccionProducto);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ProductoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoId requerido
 	 */	
	public function queryByProductoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ExternoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ExternoId requerido
 	 */	
	public function queryByExternoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna EstadoProducto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoProducto requerido
 	 */	
	public function queryByEstadoProducto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FinalId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FinalId requerido
 	 */	
	public function queryByFinalId($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProductoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoId requerido
 	 */	
	public function deleteByProductoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function deleteByValor($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function deleteByTipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ExternoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ExternoId requerido
 	 */	
	public function deleteByExternoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna EstadoProducto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value EstadoProducto requerido
 	 */	
	public function deleteByEstadoProducto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FinalId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FinalId requerido
 	 */	
	public function deleteByFinalId($value);


}
?>