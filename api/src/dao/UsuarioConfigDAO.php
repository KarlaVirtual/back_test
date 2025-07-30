<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioConfig'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioConfigDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
	 * @param String $usuarioconfigId llave primaria usuarioconfigId
	 * @param String $mandante mandante
	 */
	public function load($usuarioconfigId, $mandante);

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
 	 * @param String $usuarioconfigId llave primaria usuarioconfigId
 	 * @param String $mandante mandante
 	 */
	public function delete($usuarioconfigId, $mandante);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuarioConfig usuarioConfig
 	 */
	public function insert($usuarioConfig);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioConfig usuarioConfig
 	 */
	public function update($usuarioConfig);	

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
 	 * la columna Recarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Recarga requerido
 	 */	
	public function queryByPermiteRecarga($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Pinagent sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Pinagent requerido
 	 */	
	public function queryByPinagent($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ReciboCaja sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ReciboCaja requerido
 	 */	
	public function queryByReciboCaja($value);




	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PermiteRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PermiteRecarga requerido
 	 */
	public function deleteByPermiteRecarga($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Pinagent sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Pinagent requerido
 	 */
	public function deleteByPinagent($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ReciboCaja sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ReciboCaja requerido
 	 */
	public function deleteByReciboCaja($value);


}
?>