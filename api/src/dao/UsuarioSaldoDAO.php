<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioSaldo'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioSaldoDAO{

	/**
	 * Obtener el registro condicionado por la 
	 * llave primaria que se pasa como parámetro
	 *
 	 * @param String $usuarioId llave primaria usuarioId
 	 * @param String $mandante mandante
 	 * @param String $fecha fecha
	 */
	public function load($usuarioId, $mandante, $fecha);

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
 	 * @param String $usuarioId llave primaria usuarioId
 	 * @param String $mandante mandante
 	 * @param String $fecha fecha
 	 */
	public function delete($usuarioId, $mandante, $fecha);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param String usuarioSaldo usuarioSaldo
 	 */
	public function insert($usuarioSaldo);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioSaldo usuarioSaldo
 	 */
	public function update($usuarioSaldo);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoRecarga requerido
 	 */
	public function queryBySaldoRecarga($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoApuestas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoApuestas requerido
 	 */
	public function queryBySaldoApuestas($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoPremios sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremios requerido
 	 */
	public function queryBySaldoPremios($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPagadas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPagadas requerido
 	 */
	public function queryBySaldoNotaretPagadas($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPend sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPend requerido
 	 */
	public function queryBySaldoNotaretPend($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoAjustesEntrada sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesEntrada requerido
 	 */
	public function queryBySaldoAjustesEntrada($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoAjustesSalida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesSalida requerido
 	 */
	public function queryBySaldoAjustesSalida($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoInicial sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoInicial requerido
 	 */
	public function queryBySaldoInicial($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoFinal sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoFinal requerido
 	 */
	public function queryBySaldoFinal($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoBono requerido
 	 */
	public function queryBySaldoBono($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoRecarga sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoRecarga requerido
 	 */	
	public function deleteBySaldoRecarga($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoApuestas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoApuestas requerido
 	 */	
	public function deleteBySaldoApuestas($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoPremios sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremios requerido
 	 */	
	public function deleteBySaldoPremios($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPagadas sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPagadas requerido
 	 */	
	public function deleteBySaldoNotaretPagadas($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPend sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPend requerido
 	 */	
	public function deleteBySaldoNotaretPend($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoAjustesEntrada sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesEntrada requerido
 	 */	
	public function deleteBySaldoAjustesEntrada($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoAjustesSalida sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesSalida requerido
 	 */	
	public function deleteBySaldoAjustesSalida($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoInicial sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoInicial requerido
 	 */	
	public function deleteBySaldoInicial($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoFinal sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoFinal requerido
 	 */	
	public function deleteBySaldoFinal($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoBono sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoBono requerido
 	 */	
	public function deleteBySaldoBono($value);


}
?>