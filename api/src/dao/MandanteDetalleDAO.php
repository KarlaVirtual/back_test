<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'MandanteDetalle'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface MandanteDetalleDAO{

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
 	 * @param String $productodetalle_id llave primaria
 	 */
	public function delete($productodetalle_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String productoDetalle productoDetalle
 	 */
	public function insert($productoDetalle);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object productoDetalle productoDetalle
 	 */
	public function update($productoDetalle);	

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
 	 * la columna PKey sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PKey requerido
 	 */	
	public function queryByPKey($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PValue sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PValue requerido
 	 */	
	public function queryByPValue($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function queryByUsumodifId($value);






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProductoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProductoId requerido
 	 */	
	public function deleteByProductoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PKey sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PKey requerido
 	 */	
	public function deleteByPKey($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PValue sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PValue requerido
 	 */	
	public function deleteByPValue($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function deleteByUsumodifId($value);


}
?>