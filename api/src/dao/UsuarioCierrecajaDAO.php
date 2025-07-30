<?php namespace Backend\dao;
/**
 * Intreface DAO
 *
 * @author: DT
 * @date: 2017-09-06 19:01
 * @category    No
 * @package     No
 * @version     1.0
 * @since       No definida
 */
interface UsuarioCierrecajaDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return UsuarioCierrecaja 
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
 	 * @param UsuarioCierrecaja cargo
 	 */
	public function insert($cargo);
	
	/**
 	 * Update record in table
 	 *
 	 * @param UsuarioCierrecaja cargo
 	 */
	public function update($cargo);	

	/**
	 * Delete all rows
	 */
	public function clean();

	/** 
	 * Obtiene la colección de objetos UsuarioCierrecaja que poseen el tipo indicado
	 * @param String $value
	 * @return array Colección de UsuarioCierrecaja
	 */
	public function queryByTipo($value);

	/** 
	 * Obtiene la colección de objetos UsuarioCierrecaja que poseen la descripción indicada
	 * @param String $value
	 * @return array Colección de UsuarioCierrecaja
	 */
	public function queryByDescripcion($value);

	/** 
	 * Obtiene la colección de objetos UsuarioCierrecaja que poseen el estado indicado
	 * @param String $value
	 * @return array Colección de UsuarioCierrecaja
	 */
	public function queryByEstado($value);

	/** 
	 * Obtiene la colección de objetos UsuarioCierrecaja que poseen el mandante indicado
	 * @param String $value
	 * @return array Colección de UsuarioCierrecaja
	 */
	public function queryByMandante($value);

	/**
	 * Elimina un UsuarioCierrecaja a partir de su tipo
	 * @param String $value
	 */
	public function deleteByTipo($value);

	/**
	 * Elimina un UsuarioCierrecaja a partir de su descripción
	 * @param String $value
	 */
	public function deleteByDescripcion($value);

	/**
	 * Elimina un UsuarioCierrecaja a partir de su estado
	 * @param String $value
	 */
	public function deleteByEstado($value);

	/**
	 * Elimina un UsuarioCierrecaja a partir de su mandante
	 * @param String $value
	 */
	public function deleteByMandante($value);


}
?>