<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioMandante'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioMandanteDAO{

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
 	 * @param String $usumandante_id llave primaria
 	 */
	public function delete($usumandante_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuarioMandante usuarioMandante
 	 */
	public function insert($usuarioMandante);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioMandante usuarioMandante
 	 */
	public function update($usuarioMandante);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByUsuarioMandante($value);

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
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function queryByEmail($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Saldo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Saldo requerido
 	 */	
	public function queryBySaldo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function queryByMoneda($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function queryByPaisId($value);

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
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function queryByUsucreaId($value);

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
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioMandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioMandante requerido
 	 */	
	public function deleteByUsuarioMandante($value);

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
 	 * la columna Email sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Email requerido
 	 */	
	public function deleteByEmail($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Saldo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Saldo requerido
 	 */	
	public function deleteBySaldo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Moneda sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Moneda requerido
 	 */	
	public function deleteByMoneda($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PaisId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PaisId requerido
 	 */	
	public function deleteByPaisId($value);

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
 	 * la columna UsucreaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsucreaId requerido
 	 */	
	public function deleteByUsucreaId($value);

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


}
?>