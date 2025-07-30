<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Promocional2'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface Promocional2DAO{

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
 	 * @param String promocional2 promocional2
 	 */
	public function insert($promocional2);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object promocional2 promocional2
 	 */
	public function update($promocional2);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tiempo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tiempo requerido
 	 */	
	public function queryByTiempo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ValorBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorBono requerido
 	 */	
	public function queryByValorBono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Caducidad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Caducidad requerido
 	 */	
	public function queryByCaducidad($value);

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
 	 * la columna Tiempo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tiempo requerido
 	 */	
	public function deleteByTiempo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ValorBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ValorBono requerido
 	 */	
	public function deleteByValorBono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Caducidad sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Caducidad requerido
 	 */	
	public function deleteByCaducidad($value);

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