<?php namespace Backend\dao;
/**
 * Interfaz para el modelo o tabla 'ApiTransations'.
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 */
interface ApiTransactionsDAO{

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
 	 * @param String $trnID llave primaria
 	 */
	public function delete($trnID);
	
	/**
 	 * Insertar un registro en la base de datos
 	 *
 	 * @param Object ApiTransactions apiTransaction
 	 */
	public function insert($apiTransaction);
	
	/**
 	 * Editar un registro en la base de datos
 	 *
 	 * @param Object ApiTransactions apiTransaction
 	 */
	public function update($apiTransaction);	

	/**
	 * Eliminar todos los registros de la base de datos
	 */
	public function clean();

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna CliID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CliID requerido
 	 */
	public function queryByCliID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna MocID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MocID requerido
 	 */
	public function queryByMocID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnMonto sea igual al valor pasado como parámetro
 	 *
 	 * @param flot $value TrnMonto requerido
 	 */
	public function queryByTrnMonto($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TransactionID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransactionID requerido
 	 */
	public function queryByTransactionID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnType sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnType requerido
 	 */
	public function queryByTrnType($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnDescription sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnDescription requerido
 	 */
	public function queryByTrnDescription($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna RoundID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RoundID requerido
 	 */
	public function queryByRoundID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna History sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value History requerido
 	 */
	public function queryByHistory($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna IsRoundFinished sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value isRoundFinished requerido
 	 */
	public function queryByIsRoundFinished($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna GameID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameID requerido
 	 */
	public function queryByGameID($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnSaldo sea igual al valor pasado como parámetro
 	 *
 	 * @param float $value TrnSaldo requerido
 	 */
	public function queryByTrnSaldo($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnSaldoEX sea igual al valor pasado como parámetro
 	 *
 	 * @param float $value TrnSaldoEX requerido
 	 */
	public function queryByTrnSaldoEX($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnEstado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnEstado requerido
 	 */
	public function queryByTrnEstado($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnFecReg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecReg requerido
 	 */
	public function queryByTrnFecReg($value);

	/**
 	 * Obtener todos los registros donde se encuentre que
 	 * la columna TrnFecMod sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecMod requerido
 	 */
	public function queryByTrnFecMod($value);





	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna CliID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value CliID requerido
 	 */
	public function deleteByCliID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna MocID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value MocID requerido
 	 */
	public function deleteByMocID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnMonto sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnMonto requerido
 	 */
	public function deleteByTrnMonto($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TransactionID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TransactionID requerido
 	 */
	public function deleteByTransactionID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnType sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnType requerido
 	 */
	public function deleteByTrnType($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnDescription sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnDescription requerido
 	 */
	public function deleteByTrnDescription($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna RoundID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value RoundID requerido
 	 */
	public function deleteByRoundID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna History sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value History requerido
 	 */
	public function deleteByHistory($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna IsRoundFinished sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value IsRoundFinished requerido
 	 */
	public function deleteByIsRoundFinished($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna GameID sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value GameID requerido
 	 */
	public function deleteByGameID($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnSaldo sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnSaldo requerido
 	 */
	public function deleteByTrnSaldo($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnSaldoEx sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnSaldoEX requerido
 	 */
	public function deleteByTrnSaldoEX($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnEstado sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnEstado requerido
 	 */
	public function deleteByTrnEstado($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnFecReg sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecReg requerido
 	 */
	public function deleteByTrnFecReg($value);

	/**
 	 * Eliminar todos los registros donde se encuentre que
 	 * la columna TrnFecMod sea igual al valor pasado como parámetro
 	 *
 	 * @param String $value TrnFecMod requerido
 	 */
	public function deleteByTrnFecMod($value);

}
?>