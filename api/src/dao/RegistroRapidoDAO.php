<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'Itainment'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */	
interface RegistroRapidoDAO{

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
 	 * @param String $registro_id llave primaria
 	 */
	public function delete($registro_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String registroRapido registroRapido
 	 */
	public function insert($registroRapido);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object registroRapido registroRapido
 	 */
	public function update($registroRapido);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipoDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoDoc requerido
 	 */	
	public function queryByTipoDoc($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Cedula sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Cedula requerido
 	 */	
	public function queryByCedula($value);

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
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre1 requerido
 	 */	
	public function queryByNombre1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre2 requerido
 	 */	
	public function queryByNombre2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido1 requerido
 	 */	
	public function queryByApellido1($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido2 requerido
 	 */	
	public function queryByApellido2($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipoDoc sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoDoc requerido
 	 */	
	public function deleteByTipoDoc($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Cedula sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Cedula requerido
 	 */	
	public function deleteByCedula($value);

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

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre1 requerido
 	 */	
	public function deleteByNombre1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombre2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombre2 requerido
 	 */	
	public function deleteByNombre2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Apellido1 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido1 requerido
 	 */	
	public function deleteByApellido1($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Apellido2 sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellido2 requerido
 	 */	
	public function deleteByApellido2($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);


}
?>