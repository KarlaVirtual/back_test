<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Consecutivo'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ConsecutivoDAO{

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
 	 * @param String $consecutivo_id llave primaria
 	 */
	public function delete($consecutivo_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String consecutivo consecutivo
 	 */
	public function insert($consecutivo);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object consecutivo consecutivo
 	 */
	public function update($consecutivo);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Numero sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Numero requerido
 	 */	
	public function queryByNumero($value);







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */
	public function deleteByTipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Numero sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Numero requerido
 	 */
	public function deleteByNumero($value);


}
?>