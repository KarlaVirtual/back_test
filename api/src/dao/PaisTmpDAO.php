<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'PaisTmp'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface PaisTmpDAO{

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
 	 * @param String $pais_id llave primaria
 	 */
	public function delete($pais_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String paisTmp paisTmp
 	 */
	public function insert($paisTmp);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object paisTmp paisTmp
 	 */
	public function update($paisTmp);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Iso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Iso requerido
 	 */	
	public function queryByIso($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisNom requerido
 	 */	
	public function queryByPaisNom($value);




	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Iso sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Iso requerido
 	 */	
	public function deleteByIso($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisNom sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisNom requerido
 	 */	
	public function deleteByPaisNom($value);


}
?>