<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'DatosProveedores'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface DatosProveedoresDAO{

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
 	 * @param String $id llave primaria
 	 */
	public function delete($id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String datosProveedore datosProveedore
 	 */
	public function insert($datosProveedore);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object datosProveedore datosProveedore
 	 */
	public function update($datosProveedore);	


	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna LocalID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LocalID requerido
 	 */	
	public function queryByLocalID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RemoteID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RemoteID requerido
 	 */	
	public function queryByRemoteID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Request sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Request requerido
 	 */	
	public function queryByRequest($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Response sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Response requerido
 	 */	
	public function queryByResponse($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Timestamp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Timestamp requerido
 	 */	
	public function queryByTimestamp($value);






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna LocalID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value LocalID requerido
 	 */	
	public function deleteByLocalID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RemoteID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RemoteID requerido
 	 */	
	public function deleteByRemoteID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Request sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Request requerido
 	 */	
	public function deleteByRequest($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Response sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Response requerido
 	 */	
	public function deleteByResponse($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Timestamp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Timestamp requerido
 	 */	
	public function deleteByTimestamp($value);


}
?>