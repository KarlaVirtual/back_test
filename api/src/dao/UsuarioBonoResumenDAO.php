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
interface UsuarioBonoResumenDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioBonoResumen 
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
 	 * @param UsuarioBonoResumen producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioBonoResumen producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/**
	 * Consulta los registros por la fecha de creación.
	 *
	 * @param string $value La fecha de creación.
	 */
	public function queryByFechaCrea($value);


	/**
	 * Consulta los registros por el usuario que crea el registro.
	 *
	 * @param string $value El id del usuario que crea el registro.
	 */
	public function queryByUsucreaId($value);


	/**
	 * Consulta los registros por el usuario que modifica el registro.
	 *
	 * @param string $value El id del usuario que modifica el registro.
	 */
	public function queryByUsumodifId($value);

	/**
	 * Elimina los registros por la fecha de creación.
	 *
	 * @param string $value La fecha de creación.
	 */
	public function deleteByFechaCrea($value);


	/**
	 * Elimina los registros por el usuario que crea el registro.
	 *
	 * @param string $value El id del usuario que crea el registro.
	 */
	public function deleteByUsucreaId($value);


	/**
	 * Elimina los registros por el usuario que modifica el registro.
	 *
	 * @param string $value El id del usuario que modifica el registro.
	 */
	public function deleteByUsumodifId($value);


}
?>