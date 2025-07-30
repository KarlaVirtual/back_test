<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'PaisMoneda'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PaisMonedaDAO{

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
 	 * @param String $paismoneda_id llave primaria
 	 */
	public function delete($paismoneda_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String paisMoneda paisMoneda
 	 */
	public function insert($paisMoneda);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object paisMoneda paisMoneda
 	 */
	public function update($paisMoneda);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function queryByPaisId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function queryByMoneda($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function deleteByPaisId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function deleteByMoneda($value);


}
?>