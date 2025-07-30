<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'UsuarioSaldo2'.
 * @author Desconocido
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 *
 */
interface UsuarioSaldo2DAO{

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
 	 * @param String usuarioSaldo2 usuarioSaldo2
 	 */
	public function insert($usuarioSaldo2);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object usuarioSaldo2 usuarioSaldo2
 	 */
	public function update($usuarioSaldo2);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();





	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoRecargaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoRecargaIni requerido
 	 */	
	public function queryBySaldoRecargaIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoApuestasIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoApuestasIni requerido
 	 */	
	public function queryBySaldoApuestasIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoPremiosIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremiosIni requerido
 	 */	
	public function queryBySaldoPremiosIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoPremiosIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremiosIni requerido
 	 */	
	public function queryBySaldoNotaretPagadasIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPendIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPendIni requerido
 	 */	
	public function queryBySaldoNotaretPendIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoAjustesEntradaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesEntradaIni requerido
 	 */	
	public function queryBySaldoAjustesEntradaIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoAjustesSalidaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesSalidaIni requerido
 	 */	
	public function queryBySaldoAjustesSalidaIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoBonoIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoBonoIni requerido
 	 */	
	public function queryBySaldoBonoIni($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoRecargaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoRecargaFin requerido
 	 */	
	public function queryBySaldoRecargaFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoApuestasFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoApuestasFin requerido
 	 */	
	public function queryBySaldoApuestasFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoPremiosFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremiosFin requerido
 	 */	
	public function queryBySaldoPremiosFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPagadasFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPagadasFin requerido
 	 */	
	public function queryBySaldoNotaretPagadasFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPendFi sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPendFi requerido
 	 */	
	public function queryBySaldoNotaretPendFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoAjustesEntradaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesEntradaFin requerido
 	 */	
	public function queryBySaldoAjustesEntradaFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoAjustesSalidaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesSalidaFin requerido
 	 */	
	public function queryBySaldoAjustesSalidaFin($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna SaldoBonoFin( sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoBonoFin( requerido
 	 */	
	public function queryBySaldoBonoFin($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoRecargaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoRecargaIni requerido
 	 */	
	public function deleteBySaldoRecargaIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoApuestasIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoApuestasIni requerido
 	 */	
	public function deleteBySaldoApuestasIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoPremiosIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremiosIni requerido
 	 */	
	public function deleteBySaldoPremiosIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPagadasIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPagadasIni requerido
 	 */	
	public function deleteBySaldoNotaretPagadasIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPendIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPendIni requerido
 	 */	
	public function deleteBySaldoNotaretPendIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoAjustesEntradaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesEntradaIni requerido
 	 */	
	public function deleteBySaldoAjustesEntradaIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoAjustesSalidaIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesSalidaIni requerido
 	 */	
	public function deleteBySaldoAjustesSalidaIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoBonoIni sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoBonoIni requerido
 	 */	
	public function deleteBySaldoBonoIni($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoRecargaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoRecargaFin requerido
 	 */	
	public function deleteBySaldoRecargaFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoApuestasFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoApuestasFin requerido
 	 */	
	public function deleteBySaldoApuestasFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoPremiosFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoPremiosFin requerido
 	 */	
	public function deleteBySaldoPremiosFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPagadasFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPagadasFin requerido
 	 */	
	public function deleteBySaldoNotaretPagadasFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoNotaretPendFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoNotaretPendFin requerido
 	 */	
	public function deleteBySaldoNotaretPendFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoAjustesEntradaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesEntradaFin requerido
 	 */	
	public function deleteBySaldoAjustesEntradaFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoAjustesSalidaFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoAjustesSalidaFin requerido
 	 */	
	public function deleteBySaldoAjustesSalidaFin($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna SaldoBonoFin sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value SaldoBonoFin requerido
 	 */	
	public function deleteBySaldoBonoFin($value);


}
?>