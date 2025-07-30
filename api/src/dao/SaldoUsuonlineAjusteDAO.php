<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'SaldoUsuonlineAjuste'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface SaldoUsuonlineAjusteDAO{

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
 	 * @param String $ajuste_id llave primaria
 	 */
	public function delete($ajuste_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String saldoUsuonlineAjuste saldoUsuonlineAjuste
 	 */
	public function insert($saldoUsuonlineAjuste);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object saldoUsuonlineAjuste saldoUsuonlineAjuste
 	 */
	public function update($saldoUsuonlineAjuste);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TipoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoId requerido
 	 */	
	public function queryByTipoId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function queryByUsuarioId($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

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
 	 * la columna SaldoAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAnt requerido
 	 */	
	public function queryBySaldoAnt($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Observ sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Observ requerido
 	 */	
	public function queryByObserv($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function queryByDirIp($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MotivoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MotivoId requerido
 	 */	
	public function queryByMotivoId($value);




	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TipoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TipoId requerido
 	 */	
	public function deleteByTipoId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna UsuarioId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value UsuarioId requerido
 	 */	
	public function deleteByUsuarioId($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function deleteByValor($value);

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
 	 * la columna SaldoAnt sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAnt requerido
 	 */	
	public function deleteBySaldoAnt($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Observ sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Observ requerido
 	 */	
	public function deleteByObserv($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna DirIp sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value DirIp requerido
 	 */	
	public function deleteByDirIp($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function deleteByMandante($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MotivoId sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MotivoId requerido
 	 */	
	public function deleteByMotivoId($value);


}
?>