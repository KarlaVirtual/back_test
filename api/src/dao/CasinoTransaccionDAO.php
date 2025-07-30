<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'CasinoTransaccion'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface CasinoTransaccionDAO{

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
 	 * @param String $transaccion_id llave primaria
 	 */
	public function delete($transaccion_id);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object CasinoTransaccion casinoTransaccion
 	 */
	public function insert($casinoTransaccion);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object CasinoTransaccion casinoTransaccion
 	 */
	public function update($casinoTransaccion);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();




	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IdUser sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdUser requerido
 	 */	
	public function queryByIdUser($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IdOperacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdOperacion requerido
 	 */	
	public function queryByIdOperacion($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */	
	public function queryByValor($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna FechaTrans sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaTrans requerido
 	 */	
	public function queryByFechaTrans($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */	
	public function queryByTipo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */	
	public function queryByMandante($value);




	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IdUser sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IdUser requerido
 	 */
	public function deleteByIdUser($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Operacion sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Operacion requerido
 	 */
	public function deleteByIdOperacion($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Valor sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Valor requerido
 	 */
	public function deleteByValor($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna FechaTrans sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value FechaTrans requerido
 	 */
	public function deleteByFechaTrans($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Tipo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Tipo requerido
 	 */
	public function deleteByTipo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna Mandante sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value Mandante requerido
 	 */
	public function deleteByMandante($value);


}
?>