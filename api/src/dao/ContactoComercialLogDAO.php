<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ContactoComercialLog'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ContactoComercialLogDAO{

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
 	 * @param String $contactocomlog_id llave primaria
 	 */
	public function delete($contactocomlog_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String contactoComercialLog contactoComercialLog
 	 */
	public function insert($contactoComercialLog);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object contactoComercialLog contactoComercialLog
 	 */
	public function update($contactoComercialLog);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna ContactocomId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ContactocomId requerido
 	 */	
	public function queryByContactocomId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Fecha sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Fecha requerido
 	 */	
	public function queryByFecha($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Texto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Texto requerido
 	 */	
	public function queryByTexto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna ContactocomId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value ContactocomId requerido
 	 */
	public function deleteByContactocomId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Fecha sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Fecha requerido
 	 */
	public function deleteByFecha($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Texto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Texto requerido
 	 */
	public function deleteByTexto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>