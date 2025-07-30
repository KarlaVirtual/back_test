<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Contacto'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ContactoDAO{

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
 	 * @param String $contacto_id llave primaria
 	 */
	public function delete($contacto_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String contacto contacto
 	 */
	public function insert($contacto);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object contacto contacto
 	 */
	public function update($contacto);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre requerido
 	 */	
	public function queryByNombre($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function queryByEmail($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Telefono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Telefono requerido
 	 */	
	public function queryByTelefono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mensaje sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mensaje requerido
 	 */	
	public function queryByMensaje($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre requerido
 	 */	
	public function deleteByNombre($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function deleteByEmail($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Telefono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Telefono requerido
 	 */		
	public function deleteByTelefono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mensaje sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mensaje requerido
 	 */	
	public function deleteByMensaje($value);
	
	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>