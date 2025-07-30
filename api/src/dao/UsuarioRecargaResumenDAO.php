<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @date 2017-09-06 19:02
 * @category    No
 * @package     No
 * @version     1.0
 */
interface UsuarioRecargaResumenDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioRecargaResumen 
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
 	 * @param UsuarioRecargaResumen producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioRecargaResumen producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();


	public function queryByFechaCrea($value);

	public function queryByUsucreaId($value);

	public function queryByUsumodifId($value);



	public function deleteByFechaCrea($value);

	public function deleteByUsucreaId($value);

	public function deleteByUsumodifId($value);


}
?>