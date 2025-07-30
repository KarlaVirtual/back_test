<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'BonoError'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface BonoErrorDAO{

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
 	 * @param String $bono_id llave primaria
 	 */
	public function delete($bonoerror_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object Bono bono
 	 */
	public function insert($bonoError);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object Bono bono
 	 */
	public function update($bonoError);	

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
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */
	public function deleteByDescripcion($value);


}
?>