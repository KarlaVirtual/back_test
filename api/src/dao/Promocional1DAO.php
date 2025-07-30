<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Promocional'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface Promocional1DAO{

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
 	 * @param String $mandante llave primaria
 	 */
	public function delete($mandante);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String promocional1 promocional1
 	 */
	public function insert($promocional1);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object promocional1 promocional1
 	 */
	public function update($promocional1);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PorcenBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenBono requerido
 	 */	
	public function queryByPorcenBono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TopeBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TopeBono requerido
 	 */	
	public function queryByTopeBono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ProvisionBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProvisionBono requerido
 	 */	
	public function queryByProvisionBono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna VlrAcumulado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrAcumulado requerido
 	 */	
	public function queryByVlrAcumulado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function queryByFechaModif($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function queryByUsumodifId($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PorcenBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PorcenBono requerido
 	 */	
	public function deleteByPorcenBono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TopeBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TopeBono requerido
 	 */	
	public function deleteByTopeBono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ProvisionBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ProvisionBono requerido
 	 */	
	public function deleteByProvisionBono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna VlrAcumulado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value VlrAcumulado requerido
 	 */	
	public function deleteByVlrAcumulado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaModif sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaModif requerido
 	 */	
	public function deleteByFechaModif($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsumodifId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsumodifId requerido
 	 */	
	public function deleteByUsumodifId($value);


}
?>