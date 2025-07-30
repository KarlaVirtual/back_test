<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Submenu'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface SubmenuDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return Submenu 
	 */
	public function load($id);

	/**
	 * Get all records from table
	 */
	public function queryAll();
	
	/**
	 * Get all records from table ordered by field
	 * @Param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Delete record from table
 	 * @param submenu primary key
 	 */
	public function delete($submenu_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param Submenu submenu
 	 */
	public function insert($submenu);
	
	/**
 	 * Update record in table
 	 *
 	 * @param Submenu submenu
 	 */
	public function update($submenu);	

	/**
	 * Delete all rows
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
 	 * la columna Pagina sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Pagina requerido
 	 */	
	public function queryByPagina($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MenuId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MenuId requerido
 	 */	
	public function queryByMenuId($value);

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
 	 * la columna Pagina sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Pagina requerido
 	 */	
	public function deleteByPagina($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MenuId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MenuId requerido
 	 */	
	public function deleteByMenuId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Orden sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Orden requerido
 	 */	
	public function deleteByOrden($value);


}
?>