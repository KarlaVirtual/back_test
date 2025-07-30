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
interface UsucasinoDetalleResumenDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsucasinoDetalleResumen 
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
 	 * @param UsucasinoDetalleResumen producto
 	 */
	public function insert($usuariocomision);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsucasinoDetalleResumen producto
 	 */
	public function update($producto);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/** Obtener todos los registros que poseen la FechaCrea indicada
	 * @param string $value FechaCrea
	 * @return array Colección de usucasinoDetalleResumen
	 */
	public function queryByFechaCrea($value);

	/** Obtener todos los registros que poseen el UsucreaId indicado
	 * @param int $value UsucreaId
	 * @return array Colección de usucasinoDetalleResumen
	 */
	public function queryByUsucreaId($value);

	/** Obtener todos los registros que poseen el UsumodifId indicado
	 * @param int $value UsumodifId
	 * @return array Colección de usucasinoDetalleResumen
	 */
	public function queryByUsumodifId($value);

	/** Eliminar todos los registros que poseen la FechaCrea indicada
	 * @param string $value FechaCrea
	 */
	public function deleteByFechaCrea($value);

	/** Eliminar todos los registros que poseen el UsucreaId indicado
	 * @param int $value UsucreaId
	 */
	public function deleteByUsucreaId($value);

	/** Eliminar todos los registros que poseen el UsumodifId indicado
	 * @param int $value UsumodifId
	 */
	public function deleteByUsumodifId($value);


}
?>