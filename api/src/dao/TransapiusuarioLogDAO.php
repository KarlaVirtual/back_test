<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2017-09-15 04:48
 * @category    No
 * @package     No
 * @author      Desconocido
 * @version     1.0
 */
interface TransapiusuarioLogDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return TransapiusuarioLog 
	 */
	public function load($id);

	/**
	 * Get all records from table
	 */
	public function queryAll();
	
	/**
	 * Get all records from table ordered by field
	 * @Param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Delete record from table
 	 * @param transjuegoLog primary key
 	 */
	public function delete($transjuegolog_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param TransapiusuarioLog transjuegoLog
 	 */
	public function insert($transjuegoLog);
	
	/**
 	 * Update record in table
 	 *
 	 * @param TransapiusuarioLog transjuegoLog
 	 */
	public function update($transjuegoLog);	

	/**
	 * Delete all rows
	 */
	public function clean();

	
	/**
	 * Consulta registros por el ID de Transjuego.
	 *
	 * @param int $value El ID de Transjuego a consultar.
	 */
	public function queryByTransjuegoId($value);

	/**
	 * Consulta registros por el tipo de transacción.
	 *
	 * @param string $value El tipo de transacción a consultar.
	 */
	public function queryByTipo($value);

	/**
	 * Consulta registros por el ID de Transacción.
	 *
	 * @param int $value El ID de Transacción a consultar.
	 */
	public function queryByTransaccionId($value);

	/**
	 * Consulta registros por el valor de la transacción.
	 *
	 * @param float $value El valor de la transacción a consultar.
	 */
	public function queryByTValue($value);

	/**
	 * Consulta registros por la fecha de creación.
	 *
	 * @param string $value La fecha de creación a consultar.
	 */
	public function queryByFechaCrea($value);


	/**
	 * Consulta registros por el ID de usuario creador.
	 *
	 * @param int $value El ID de usuario creador a consultar.
	 */
	public function queryByUsucreaId($value);

	/**
	 * Consulta registros por la fecha de modificación.
	 *
	 * @param string $value La fecha de modificación a consultar.
	 */
	public function queryByFechaModif($value);


	/**
	 * Consulta registros por el ID de usuario modificador.
	 *
	 * @param int $value El ID de usuario modificador a consultar.
	 */
	public function queryByUsumodifId($value);


	/**
	 * Elimina registros por el ID de Transjuego.
	 *
	 * @param int $value El ID de Transjuego a eliminar.
	 */
	public function deleteByTransjuegoId($value);
	
	/**
	 * Elimina registros por el tipo de transacción.
	 *
	 * @param string $value El tipo de transacción a eliminar.
	 */
	public function deleteByTipo($value);

	/**
	 * Elimina registros por el ID de Transacción.
	 *
	 * @param int $value El ID de Transacción a eliminar.
	 */
	public function deleteByTransaccionId($value);

	/**
	 * Elimina registros por el valor de la transacción.
	 *
	 * @param float $value El valor de la transacción a eliminar.
	 */
	public function deleteByTValue($value);

	/**
	 * Elimina registros por la fecha de creación.
	 *
	 * @param string $value La fecha de creación a eliminar.
	 */
	public function deleteByFechaCrea($value);


	/**
	 * Elimina registros por el ID de usuario creador.
	 *
	 * @param int $value El ID de usuario creador a eliminar.
	 */
	public function deleteByUsucreaId($value);

	/**
	 * Elimina registros por la fecha de modificación.
	 *
	 * @param string $value La fecha de modificación a eliminar.
	 */
	public function deleteByFechaModif($value);


	/**
	 * Elimina registros por el ID de usuario modificador.
	 *
	 * @param int $value El ID de usuario modificador a eliminar.
	 */
	public function deleteByUsumodifId($value);


}
?>