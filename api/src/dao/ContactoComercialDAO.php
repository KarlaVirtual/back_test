<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ContactoComercial'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface ContactoComercialDAO{

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
	public function delete($contactocom_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String consecutivo consecutivo
 	 */
	public function insert($contactoComercial);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object consecutivo consecutivo
 	 */
	public function update($contactoComercial);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();







	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Nombres sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombres requerido
 	 */	
	public function queryByNombres($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Apellidos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellidos requerido
 	 */	
	public function queryByApellidos($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Empresa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Empresa requerido
 	 */	
	public function queryByEmpresa($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function queryByEmail($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Skype sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Skype requerido
 	 */	
	public function queryBySkype($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function queryByPaisId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DeptoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoId requerido
 	 */	
	public function queryByDeptoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Direccion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Direccion requerido
 	 */	
	public function queryByDireccion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Telefono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Telefono requerido
 	 */	
	public function queryByTelefono($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Observacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Observacion requerido
 	 */	
	public function queryByObservacion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

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
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);







	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Nombres sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Nombres requerido
 	 */
	public function deleteByNombres($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Apellidos sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Apellidos requerido
 	 */
	public function deleteByApellidos($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Empresa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Empresa requerido
 	 */
	public function deleteByEmpresa($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */
	public function deleteByEmail($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Skype sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Skype requerido
 	 */	
	public function deleteBySkype($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */
	public function deleteByPaisId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DeptoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DeptoId requerido
 	 */
	public function deleteByDeptoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Direccion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Direccion requerido
 	 */
	public function deleteByDireccion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Telefono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Telefono requerido
 	 */
	public function deleteByTelefono($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Observacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Observacion requerido
 	 */
	public function deleteByObservacion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */
	public function deleteByFechaCrea($value);

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

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */
	public function deleteByTipo($value);


}
?>