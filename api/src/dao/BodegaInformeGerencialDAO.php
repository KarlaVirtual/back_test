<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author: DT
 * @date: 2017-09-06 19:02
 * @category    No
 * @package     No
 * @version     1.0
 */
interface BodegaInformeGerencialDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
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
 	 * @param BodegaInformeGerencial producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param BodegaInformeGerencial producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();


    /** Carga los registros por fechaCrea
     *@param string $value Fecha de creación solicitada
     */
	public function queryByFechaCrea($value);


    /** Carga los registros por UsucreId
     *@param int $value ID del usuario
     */
	public function queryByUsucreaId($value);

    /** Carga los registros por UsumodifId
     *@param int $value ID del usuario
     */
	public function queryByUsumodifId($value);

    /** Elimina los registros por FechaCrea indicada
     *@param int $value FechaCrea
     */
    public function deleteByFechaCrea($value);


    /** Elimina los registros por UsucreId
     *@param int $value UsucreaId
     */
	public function deleteByUsucreaId($value);


    /** Elimina los registros por UsumodifId
     *@param int $value UsumodifId
     */
	public function deleteByUsumodifId($value);
}
?>