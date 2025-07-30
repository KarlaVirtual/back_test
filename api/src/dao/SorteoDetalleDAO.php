<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'SorteoDetalle'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface SorteoDetalleDAO{
	
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
 	 * @param String $sorteodetalle_id llave primaria
 	 */
	public function delete($sorteodetalle_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String SorteoDetalle SorteoDetalle
 	 */
	public function insert($SorteoDetalle);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object SorteoDetalle SorteoDetalle
 	 */
	public function update($SorteoDetalle);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();


}
?>