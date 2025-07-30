<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author: Daniel Tamayo <it@virtualsoft.tech>
 * @date: 2017-09-06 19:01
 * @category    No
 * @package     No
 * @version     1.0
 */
interface LealtadHistorialDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return LealtadHistorial 
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
 	 * @param cargo primary key
 	 */
	public function delete($cargo_id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param LealtadHistorial cargo
 	 */
	public function insert($cargo);
	
	/**
 	 * Update record in table
 	 *
 	 * @param LealtadHistorial cargo
 	 */
	public function update($cargo);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByTipo($value);

	public function queryByDescripcion($value);

	public function queryByEstado($value);

	public function queryByMandante($value);


	public function deleteByTipo($value);

	public function deleteByDescripcion($value);

	public function deleteByEstado($value);

	public function deleteByMandante($value);


}
?>
