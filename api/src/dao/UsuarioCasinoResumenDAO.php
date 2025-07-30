<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date: 2017-09-06 19:02
 * @category    No
 * @package     No
 * @version     1.0
 */
interface UsuarioCasinoResumenDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioCasinoResumen 
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
 	 * @param producto primary key
 	 */
	public function delete($usumarketing_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param UsuarioCasinoResumen producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioCasinoResumen producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/**
	 * Query the database for records by the creation date.
	 *
	 * @param string $value The creation date to query by.
	 * @return array An array of records that match the query.
	 */
	public function queryByFechaCrea($value);

	/**
	 * Query the database for records by the user ID.
	 *
	 * @param int $value The user ID to query by.
	 * @return array An array of records that match the query.
	 */
	public function queryByUsucreaId($value);

	/**
	 * Query the database for records by the user ID.
	 *
	 * @param int $value The user ID to query by.
	 * @return array An array of records that match the query.
	 */
	public function queryByUsumodifId($value);

	/**
	 * Delete all records where the creation date matches the given value.
	 *
	 * @param string $value The creation date to query by.
	 */
	public function deleteByFechaCrea($value);

	/**
	 * Delete all records where the user ID matches the given value.
	 *
	 * @param int $value The user ID to query by.
	 */
	public function deleteByUsucreaId($value);

	/**
	 * Delete all records where the user ID matches the given value.
	 *
	 * @param int $value The user ID to query by.
	 */
	public function deleteByUsumodifId($value);


}
?>