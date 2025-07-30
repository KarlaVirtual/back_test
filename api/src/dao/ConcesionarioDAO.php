<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Consecionario'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ConcesionarioDAO{

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
	public function delete($concesionario_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String cargo_id cargo_id
 	 */
	public function insert($concesionario);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object cargo cargo
 	 */
	public function update($concesionario);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsupadreId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsupadreId requerido
 	 */	
	public function queryByUsupadreId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuhijoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuhijoId requerido
 	 */	
	public function queryByUsuhijoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Usupadre2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Usupadre2Id requerido
 	 */	
	public function queryByUsupadre2Id($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsupadreId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsupadreId requerido
 	 */	
	public function deleteByUsupadreId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuhijoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuhijoId requerido
 	 */
	public function deleteByUsuhijoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Usupadre2Id sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Usupadre2Id requerido
 	 */
	public function deleteByUsupadre2Id($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);


}
?>