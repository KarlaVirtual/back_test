<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Ingreso'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface IngresoDAO{

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
 	 * @param String $cargo_id llave primaria
 	 */
	public function delete($cargo_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String cargo cargo
 	 */
	public function insert($cargo);

	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object cargo cargo
 	 */
	public function update($cargo);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();



}
?>