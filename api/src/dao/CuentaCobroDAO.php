<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'CuentaCobro'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface CuentaCobroDAO{

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
 	 * @param String $cuenta_id llave primaria
 	 */
	public function delete($cuenta_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String cuentaCobro cuentaCobro
 	 */
	public function insert($cuentaCobro);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object cuentaCobro cuentaCobro
 	 */
	public function update($cuentaCobro);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();






	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function queryByFechaCrea($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function queryByFechaPago($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function queryByPuntoventaId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function queryByEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function queryByClave($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Impresa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Impresa requerido
 	 */	
	public function queryByImpresa($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MediopagoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MediopagoId requerido
 	 */	
	public function queryByMediopagoId($value);






	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaCrea sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaCrea requerido
 	 */	
	public function deleteByFechaCrea($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function deleteByValor($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaPago sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaPago requerido
 	 */	
	public function deleteByFechaPago($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna PuntoventaId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value PuntoventaId requerido
 	 */	
	public function deleteByPuntoventaId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Estado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Estado requerido
 	 */	
	public function deleteByEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Clave sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Clave requerido
 	 */	
	public function deleteByClave($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Impresa sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Impresa requerido
 	 */	
	public function deleteByImpresa($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MediopagoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MediopagoId requerido
 	 */	
	public function deleteByMediopagoId($value);


}
?>