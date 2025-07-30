<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'RangoIngreso'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface RangoIngresoDAO{

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
 	 * @param String $rangoingreso_id llave primaria
 	 */
	public function delete($rangoingreso_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String rangoIngreso rangoIngreso
 	 */
	public function insert($rangoIngreso);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object rangoIngreso rangoIngreso
 	 */
	public function update($rangoIngreso);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function queryByDescripcion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Orden sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Orden requerido
 	 */	
	public function queryByOrden($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function deleteByDescripcion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Orden sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Orden requerido
 	 */	
	public function deleteByOrden($value);


}
?>