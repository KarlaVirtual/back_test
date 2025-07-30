<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'DepartamentoCopy'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface DepartamentoCopyDAO{

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
 	 * @param String $depto_id llave primaria
 	 */
	public function delete($depto_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String departamentoCopy departamentoCopy
 	 */
	public function insert($departamentoCopy);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object departamentoCopy departamentoCopy
 	 */
	public function update($departamentoCopy);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DeptoCod sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoCod requerido
 	 */	
	public function queryByDeptoCod($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DeptoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoNom requerido
 	 */	
	public function queryByDeptoNom($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function queryByPaisId($value);






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DeptoCod sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoCod requerido
 	 */	
	public function deleteByDeptoCod($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DeptoNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoNom requerido
 	 */	
	public function deleteByDeptoNom($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function deleteByPaisId($value);


}
?>