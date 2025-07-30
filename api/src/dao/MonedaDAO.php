<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Moneda'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface MonedaDAO{

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
 	 * @param String $moneda llave primaria
 	 */
	public function delete($moneda);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String moneda moneda
 	 */
	public function insert($moneda);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object moneda moneda
 	 */
	public function update($moneda);

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CodNumerico sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CodNumerico requerido
 	 */	
	public function queryByCodNumerico($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */	
	public function queryByDescripcion($value);



	

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CodNumerico sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CodNumerico requerido
 	 */
	public function deleteByCodNumerico($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Descripcion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Descripcion requerido
 	 */
	public function deleteByDescripcion($value);


}
?>